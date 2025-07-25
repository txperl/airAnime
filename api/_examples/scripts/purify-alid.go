package main

import (
	"encoding/json"
	"fmt"
	"os"
	"strings"
)

func main() {
	var (
		b         []byte
		rawList   []AlidItem
		finalList []AlidItem
	)

	// Read raw
	b, _ = os.ReadFile("../data-raw/alid.json")
	json.Unmarshal(b, &rawList)
	for _, item := range rawList {
		fmt.Println(item.Link)
		if item.Title == "" || item.Link == "" {
			continue
		}
		tempUrls := strings.Split(item.Link, "/")
		finalList = append(finalList, AlidItem{
			Title: item.Title,
			Link:  tempUrls[len(tempUrls)-1],
		})
	}

	// Write final
	b, _ = json.Marshal(finalList)
	os.WriteFile("../data/alid_ruach.json", b, 0644)
}

type AlidItem struct {
	Title string `json:"title"`
	Link  string `json:"link"`
}
