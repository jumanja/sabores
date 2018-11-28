#users_api
echo "$myapi"

# Intenta retornar la información del usuario que corresponda al id enviado, se espera ok
curl -X GET -o ../../results/users_id_3.json --data @../login/logintoken.txt "$myapi"/users/3

# Intenta retornar la información del usuario que corresponda al id enviado, se espera error
curl -X GET -o ../../results/users_id_error.json --data @../login/logintoken.txt "$myapi"/users/354992
