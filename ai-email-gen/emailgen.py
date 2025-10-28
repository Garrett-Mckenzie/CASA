import requests, json

def main():
    import argparse
    parser = argparse.ArgumentParser()
    parser.add_argument("--reason", required=True)
    parser.add_argument("--recipient", required=True)
    parser.add_argument("--sender", required=True)
    args = parser.parse_args()

    # Build the light, professional prompt
    prompt = f"""
    You are an assistant that writes short, professional, and warm emails.
    The sender is {args.sender}.
    The recipient email is {args.recipient}.
    The reason for sending is: {args.reason}.
    Write a concise, friendly email for this purpose.
    Do not include any bracketed info for me to fill out, if you dont know information leave it out!
    """

    # Use the Ollama HTTP API (model hosted in your container)
    url = "http://localhost:11434/api/generate"
    payload = {
        "model": "qwen3:14b",
        "prompt": prompt,
        "stream": False
    }

    try:
        response = requests.post(url, json=payload)
        response.raise_for_status()
        data = response.json()
        print(data.response)
        print(data.get("response", "").strip())
    except requests.exceptions.RequestException as e:
        print(f"Error communicating with Ollama API: {e}")

if __name__ == "__main__":
    main()
