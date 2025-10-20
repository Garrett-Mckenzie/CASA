import requests

url = "http://localhost:11434/api/pull"
payload = {"model": "qwen3:14b"}

try:
    with requests.post(url, json=payload, stream=True) as resp:
        for line in resp.iter_lines():
            if line:
                print(line.decode())
except requests.exceptions.RequestException as e:
    print("Error:", e)
