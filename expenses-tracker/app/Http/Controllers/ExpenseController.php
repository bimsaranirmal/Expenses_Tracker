<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Notification;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $categories = $user->categories;

        // If no filter in URL → use This Month by default
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to',   now()->endOfMonth()->toDateString());
        $categoryId = $request->input('category_id');

        $query = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$from, $to]);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $expenses = $query->orderBy('date', 'desc')->get();

        $chartData = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$from, $to])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get();

    // --- Notification Logic ---
    $now = now();
    $firstThisMonth = $now->copy()->startOfMonth()->toDateString();
    $lastThisMonth = $now->copy()->endOfMonth()->toDateString();
    $firstLastMonth = $now->copy()->subMonth()->startOfMonth()->toDateString();
    $lastLastMonth = $now->copy()->subMonth()->endOfMonth()->toDateString();

    // 1. Category limit exceeded for this month
    foreach ($categories as $cat) {
        if ($cat->limit_amount > 0) {
            $catTotal = Expense::where('user_id', $user->id)
                ->where('category_id', $cat->id)
                ->whereBetween('date', [$firstThisMonth, $lastThisMonth])
                ->sum('amount');
            if ($catTotal > $cat->limit_amount) {
                $monthYear = $now->format('F Y');
                $message = "You have exceeded the limit for category '{$cat->name}' in {$monthYear}.";
                $existing = Notification::where('user_id', $user->id)
                    ->where('message', $message)
                    ->where(function($q){ $q->where('deleted', false)->orWhereNull('deleted'); })
                    ->first();
                if (!$existing) {
                    $notification = Notification::create([
                        'user_id' => $user->id,
                        'message' => $message,
                        'read' => false,
                        'deleted' => false
                    ]);
                    // Send email if enabled
                    if ($user->email_notifications && $user->email) {
                        \Mail::raw($message, function($mail) use ($user) {
                            $mail->to($user->email)
                                ->subject('Expense Notification');
                        });
                    }
                    // Send SMS if enabled and mobile number exists
                    if ($user->sms_notifications && $user->mobile_number) {
                        $smsApiUrl = 'https://app.text.lk/api/http/sms/send';
                        $smsParams = [
                            'recipient' => $user->mobile_number,
                            'message'   => $message,
                            'api_token' => '2363|CHS8YKmw9FQBvFTylIqt7I8mJoEDlBJ7lMnNi4Fqa2d3d61f',
                            'sender_id' => 'TextLKDemo', // Replace with your registered sender ID
                        ];
                        try {
                            $smsResponse = Http::post($smsApiUrl, $smsParams);
                            \Log::info('SMS API response', [
                                'status' => $smsResponse->status(),
                                'body' => $smsResponse->body(),
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('SMS sending failed: ' . $e->getMessage());
                        }
                    }
                }
            }
        }
    }


    // 2. Category expenses exceeded last month
    foreach ($categories as $cat) {
        $catTotalThisMonth = Expense::where('user_id', $user->id)
            ->where('category_id', $cat->id)
            ->whereBetween('date', [$firstThisMonth, $lastThisMonth])
            ->sum('amount');
        $catTotalLastMonth = Expense::where('user_id', $user->id)
            ->where('category_id', $cat->id)
            ->whereBetween('date', [$firstLastMonth, $lastLastMonth])
            ->sum('amount');
        if ($catTotalLastMonth > 0 && $catTotalThisMonth > $catTotalLastMonth) {
            $monthYear = $now->format('F Y');
            $message = "You have exceeded last month's expenses for category '{$cat->name}' in {$monthYear}.";
            $existing = Notification::where('user_id', $user->id)
                ->where('message', $message)
                ->where(function($q){ $q->where('deleted', false)->orWhereNull('deleted'); })
                ->first();
            if (!$existing) {
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'message' => $message,
                    'read' => false,
                    'deleted' => false
                ]);
                if ($user->email_notifications && $user->email) {
                    \Mail::raw($message, function($mail) use ($user) {
                        $mail->to($user->email)
                            ->subject('Expense Notification');
                    });
                }
                if ($user->sms_notifications && $user->mobile_number) {
                    $smsApiUrl = 'https://app.text.lk/api/http/sms/send';
                    $smsParams = [
                        'recipient' => $user->mobile_number,
                        'message'   => $message,
                        'api_token' => '2363|CHS8YKmw9FQBvFTylIqt7I8mJoEDlBJ7lMnNi4Fqa2d3d61f',
                        'sender_id' => 'TextLKDemo', // Replace with your registered sender ID
                    ];
                    try {
                        $smsResponse = Http::post($smsApiUrl, $smsParams);
                        \Log::info('SMS API response', [
                            'status' => $smsResponse->status(),
                            'body' => $smsResponse->body(),
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('SMS sending failed: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    // 3. Total expenses exceeded last month
    $totalThisMonth = Expense::where('user_id', $user->id)
        ->whereBetween('date', [$firstThisMonth, $lastThisMonth])
        ->sum('amount');
    $totalLastMonth = Expense::where('user_id', $user->id)
        ->whereBetween('date', [$firstLastMonth, $lastLastMonth])
        ->sum('amount');
    if ($totalLastMonth > 0 && $totalThisMonth > $totalLastMonth) {
        $monthYear = $now->format('F Y');
        $message = "You have exceeded your total expenses compared to last month in {$monthYear}.";
        $existing = Notification::where('user_id', $user->id)
            ->where('message', $message)
            ->where(function($q){ $q->where('deleted', false)->orWhereNull('deleted'); })
            ->first();
        if (!$existing) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'message' => $message,
                'read' => false,
                'deleted' => false
            ]);
            if ($user->email_notifications && $user->email) {
                \Mail::raw($message, function($mail) use ($user) {
                    $mail->to($user->email)
                        ->subject('Expense Notification');
                });
            }
            if ($user->sms_notifications && $user->mobile_number) {
                $smsApiUrl = 'https://app.text.lk/api/http/sms/send';
                $smsParams = [
                    'recipient' => $user->mobile_number,
                    'message'   => $message,
                    'api_token' => '2363|CHS8YKmw9FQBvFTylIqt7I8mJoEDlBJ7lMnNi4Fqa2d3d61f',
                    'sender_id' => 'TextLKDemo', // Replace with your registered sender ID
                ];
                try {
                    $smsResponse = Http::post($smsApiUrl, $smsParams);
                    \Log::info('SMS API response', [
                        'status' => $smsResponse->status(),
                        'body' => $smsResponse->body(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('SMS sending failed: ' . $e->getMessage());
                }
            }
        }
    }

    // 4. Predict Next Month's Expense
    $predictionService = new \App\Services\PredictionService();
    $predictionResult = $predictionService->predictNextMonthExpense($user->id);
    $predictedExpense = $predictionResult['prediction'];
    $predictionConfidence = $predictionResult['accuracy'] ?? (($predictionResult['r_squared'] ?? 0) * 100); // Use accuracy if set, else r_squared converted to %
    $predictionSource = $predictionResult['source'] ?? 'Polynomial';

    // 5. Advanced Analytics
    $anomalyService = new \App\Services\AnomalyService();
    // Restriction: Anomaly detection ONLY for the current month
    $currentPropsFrom = now()->startOfMonth()->toDateString();
    $currentPropsTo = now()->endOfMonth()->toDateString();
    $anomalies = $anomalyService->detectAnomalies($user->id, $currentPropsFrom, $currentPropsTo);

    $insightService = new \App\Services\InsightService();
    $insights = $insightService->generateInsights($user->id);

    return view('expenses.index', compact('expenses', 'categories', 'chartData', 'from', 'to', 'categoryId', 'predictedExpense', 'predictionConfidence', 'predictionSource', 'anomalies', 'insights'));
}


// Fetch notifications for the authenticated user



    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note
        ]);

        return response()->json(['message' => 'Expense added successfully!']);
    }

    public function update(Request $request)
    {
        $expense = Expense::findOrFail($request->id);

        $expense->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        return response()->json(['message' => 'Expense updated']);
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return response()->json(['message' => 'Expense deleted']);
    }

    public function categoryExpenses($categoryId)
    {
        $expenses = Expense::where('user_id', auth()->id())
            ->where('category_id', $categoryId)
            ->with('category')
            ->get();

        return response()->json($expenses);
    }

    // Export expenses table as PDF
    public function exportPdf(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->endOfMonth()->toDateString());

        $expenses = Expense::where('user_id', auth()->id())
            ->whereBetween('date', [$from, $to])
            ->orderBy('date', 'desc')
            ->get();

        $categories = auth()->user()->categories;

        // Load a simple Blade view for PDF
        $pdf = \PDF::loadView('expenses.pdf', compact('expenses', 'categories', 'from', 'to'));
        return $pdf->download('expenses.pdf');
    }

    public function notifications()
    {
        $user = auth()->user();
        $now = now();
        $firstThisMonth = $now->copy()->startOfMonth();
        $lastThisMonth = $now->copy()->endOfMonth();
        $notifications = Notification::where('user_id', $user->id)
            ->where(function($q){ $q->where('deleted', false)->orWhereNull('deleted'); })
            ->whereBetween('created_at', [$firstThisMonth, $lastThisMonth])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'message', 'read', 'created_at']);
        return response()->json($notifications);
    }

// Mark a notification as read
public function markNotificationRead($id)
{
    $user = auth()->user();
    $notification = Notification::where('user_id', $user->id)
        ->where('id', $id)
        ->first();

    if ($notification) {
        $notification->read = true;
        $notification->save();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}

// Delete a notification (soft delete)
public function deleteNotification($id)
{
    $user = auth()->user();
    $notification = Notification::where('user_id', $user->id)
        ->where('id', $id)
        ->first();

    if ($notification) {
        $notification->deleted = true;
        $notification->save();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}

    public function sendReport(Request $request)
    {
        $user = auth()->user();
        
        // Generate content
        $insightService = new \App\Services\InsightService();
        $insights = $insightService->generateInsights($user->id);
        
        $anomalyService = new \App\Services\AnomalyService();
        $anomalies = $anomalyService->detectAnomalies($user->id);
        
        $message = "Financial Report for " . now()->format('F Y') . ":\n";
        foreach ($insights as $insight) {
            $message .= "- " . $insight['message'] . "\n";
        }
        if (count($anomalies) > 0) {
            $message .= "- " . count($anomalies) . " abnormal expenses detected.\n";
        } else {
            $message .= "- No abnormal expenses detected.\n";
        }
        $message .= "Check your dashboard for details.";

        $sentChannels = [];

        // Send Email
        if ($user->email_notifications && $user->email) {
            \Mail::raw($message, function($mail) use ($user) {
                $mail->to($user->email)
                    ->subject('Monthly Financial Report');
            });
            $sentChannels[] = 'Email';
        }

        // Send SMS
        if ($user->sms_notifications && $user->mobile_number) {
            $smsApiUrl = 'https://app.text.lk/api/http/sms/send';
            $smsParams = [
                'recipient' => $user->mobile_number,
                'message'   => $message,
                'api_token' => '2363|CHS8YKmw9FQBvFTylIqt7I8mJoEDlBJ7lMnNi4Fqa2d3d61f',
                'sender_id' => 'TextLKDemo',
            ];
            try {
                Http::post($smsApiUrl, $smsParams);
                $sentChannels[] = 'SMS';
            } catch (\Exception $e) {
                \Log::error('SMS sending failed: ' . $e->getMessage());
            }
        }

        if (empty($sentChannels)) {
            return response()->json(['message' => 'Report generated, but no notification channels are enabled.'], 200);
        }

        return response()->json(['message' => 'Report sent via ' . implode(' & ', $sentChannels) . '!']);
    }

}
