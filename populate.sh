#!/usr/bin/env bash
#
# Populate redis store for fun

host="http://localhost:8080/messages"

for i in 0 1 2 3 4 5; do
	curl ${host} \
		-X POST \
		-d "{\"body\":\"${i}\"}" \
		-H 'Content-Type: application/json'
done
