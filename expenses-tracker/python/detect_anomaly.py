import sys
import json
import joblib
import pandas as pd
import numpy as np
import os
import keras

def detect_anomalies():
    mode = 'expense'
    if len(sys.argv) > 1 and sys.argv[1] in ['expense', 'income']:
        mode = sys.argv[1]
    
    input_data = sys.stdin.read()
    if not input_data:
        print(json.dumps({"error": "No input data provided"}))
        return

    try:
        data = json.loads(input_data)
        
        # 1. Load Files
        model_name = f'dl_anomaly_{mode}_model.keras'
        scaler_name = f'scaler_anomaly_{mode}.pkl'
        map_name = f'category_{mode}_mapping.json'
        threshold_name = f'threshold_{mode}.json'
        
        base_dir = os.path.dirname(__file__)
        model_path = os.path.join(base_dir, model_name)
        scaler_path = os.path.join(base_dir, scaler_name)
        threshold_path = os.path.join(base_dir, threshold_name)
        map_path = os.path.join(base_dir, map_name)
        
        if not all(os.path.exists(p) for p in [model_path, scaler_path, threshold_path, map_path]):
            # Silently fallback to old models if new ones don't exist yet
            # However, since we are upgrading, we expect these to be there after training
            print(json.dumps({"error": "DL models or required files not found. Please train models first."}))
            return
            
        model = keras.models.load_model(model_path, compile=False)
        scaler = joblib.load(scaler_path)
        with open(map_path, 'r') as f:
            cat_to_id = json.load(f)
        with open(threshold_path, 'r') as f:
            threshold = json.load(f)['threshold']

        results = {}
        for item in data:
            item_id = item['id']
            cat_name = item.get('category_name', 'Unknown')
            amount = item['amount']
            
            cat_label = cat_to_id.get(cat_name, -1)
            
            # Prepare and Scale
            X_input = np.array([[cat_label, amount]])
            X_scaled = scaler.transform(X_input)
            
            # Reconstruct and Calculate MSE
            reconstruction = model.predict(X_scaled, verbose=0)
            mse = np.mean(np.power(X_scaled - reconstruction, 2))
            
            if mse > threshold:
                results[item_id] = {
                    "ai_flag": True,
                    "message": f"AI flagged this {cat_name} {mode} as unusual.",
                    "score": float(mse)
                }
        
        print(json.dumps(results))

    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    detect_anomalies()
