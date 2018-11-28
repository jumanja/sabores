#users_api
echo "$myapi"

# Intenta contar cuantos usuarios hay en la bd, se espera cuenta ok
curl -X GET -o ../../results/servs_count.json --data @../login/logintoken.txt "$myapi"/servs/count
