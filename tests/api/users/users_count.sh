#users_api
echo "$myapi"

# Intenta contar cuantos usuarios hay en la bd, se espera cuenta ok
curl -X GET -o ../../results/users_count.json --data @../login/logintoken.txt "$myapi"/users/count
