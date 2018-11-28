#users_api
echo "$myapi"

# Intenta contar cuantas grupos hay en la bd, se espera cuenta ok
curl -X GET -o ../../results/groups_count.json --data @../login/logintoken.txt "$myapi"/groups/count
