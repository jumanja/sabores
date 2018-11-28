#users_api
echo "$myapi"

# Intenta retornar todos las grupos de la base de datos, se espera ok
curl -X GET -o ../../results/groups_all.json --data @../login/logintoken.txt "$myapi"/groups
