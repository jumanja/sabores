#users_api
echo "$myapi"

# Intenta retornar todos los servicios de la base de datos, se espera ok
curl -X GET -o ../../results/servs_all.json --data @../login/logintoken.txt "$myapi"/servs
