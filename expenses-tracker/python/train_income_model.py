import sys
import joblib
import pandas as pd
import os

# Load the LKR model
model_path = os.path.join(os.path.dirname(__file__), 'lkr_expense_model.pkl')

if not os.path.exists(model_path):
    sys.exit(1) # Silent exit for integration

try:
    model = joblib.load(model_path)
    
    # Expecting: [Last_Month] [2_Months_Ago] [3_Months_Ago]
    if len(sys.argv) == 4:
        input_data = [float(arg) for arg in sys.argv[1:4]]
        
        # DataFrame ensures no feature name warnings
        input_df = pd.DataFrame([input_data], 
                                columns=['Month_1_Ago', 'Month_2_Ago', 'Month_3_Ago'])
        
        prediction = model.predict(input_df)[0]
        print(f"{prediction:.2f}")
    else:
        sys.exit(1)

except Exception:
    sys.exit(1)