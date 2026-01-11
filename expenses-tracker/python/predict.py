import sys
import joblib
import pandas as pd
import os

# 1. Parse Arguments (Expected: [mode] [Last_Month] [2_Months_Ago] [3_Months_Ago])
if len(sys.argv) != 5:
    sys.exit(1)

mode = sys.argv[1] # 'expense' or 'income'
args = [float(arg) for arg in sys.argv[2:5]]

# 2. Determine Model and Scaler Paths
# Check for Deep Learning model first
dl_model_file = f'dl_{mode}_model.keras'
scaler_file = f'scaler_{mode}.pkl'
model_path_dl = os.path.join(os.path.dirname(__file__), dl_model_file)
scaler_path = os.path.join(os.path.dirname(__file__), scaler_file)

# Legacy Random Forest fallback
rf_model_file = 'lkr_expense_model.pkl' if mode == 'expense' else 'lkr_income_model.pkl'
model_path_rf = os.path.join(os.path.dirname(__file__), rf_model_file)

try:
    if os.path.exists(model_path_dl) and os.path.exists(scaler_path):
        import keras
        model = keras.models.load_model(model_path_dl, compile=False)
        scaler = joblib.load(scaler_path)
        
        input_df = pd.DataFrame([args], columns=['Month_1_Ago', 'Month_2_Ago', 'Month_3_Ago'])
        input_scaled = scaler.transform(input_df)
        
        prediction = model.predict(input_scaled, verbose=0)[0][0]
        print(f"{prediction:.2f}")
    else:
        sys.exit(1)

except Exception:
    sys.exit(1)
