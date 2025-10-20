package main

import (
	"log"
	"net/http"
)

func upload(w http.ResponseWriter, r *http.Request) {

}

func handle() {
	http.HandleFunc("/upload", upload)
	log.Fatal(http.ListenAndServe(":8080", nil))
}

func main() {
	handle()
}
