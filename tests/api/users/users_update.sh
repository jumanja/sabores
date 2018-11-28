#users_api
echo "$myapi"

# Intenta modificar los datos para el update, insertando primero los datos del token de la sesión
cat ../login/logintoken.txt ../ampersand.txt ../users/users_update_params.txt > ../users/users_update_data.txt


# Intenta modificar los datos de un usuario, se espera ok
curl -X PUT -o ../../results/users_update.json --data @../users/users_update_data.txt "$myapi"/users
