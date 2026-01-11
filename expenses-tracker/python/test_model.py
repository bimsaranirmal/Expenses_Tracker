import joblib
import pandas as pd
import json
import os

def test():
    model = joblib.load('anomaly_model.pkl')
    with open('category_mapping.json', 'r') as f:
        mapping = json.load(f)
    
    cat_name = 'Coffee'
    amount = 50000
    cat_id = mapping.get(cat_name, -1)
    
    df = pd.DataFrame([[cat_id, amount]], columns=['category_label', 'amount'])
    prediction = model.predict(df)[0]
    
    print(f"Category: {cat_name}, Amount: {amount}")
    print(f"Mapped ID: {cat_id}")
    print(f"Prediction: {prediction} ('-1' means ANOMALY, '1' means NORMAL)")

if __name__ == "__main__":
    test()
