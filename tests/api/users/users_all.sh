#users_api
echo "$myapi"

# Intenta retornar todos los usuarios de la base de datos, se espera ok
curl -X GET -o ../../results/users_all.json --data @../login/logintoken.txt "$myapi"/users
