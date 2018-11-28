#users_api
echo "$myapi"

# Intenta modificar los datos para el update, insertando primero los datos del token de la sesión
cat ../login/logintoken.txt ../ampersand.txt ../users/users_update_params2.txt > ../users/users_update_data2.txt


# Intenta modificar los datos de un usuario, se espera ok
curl -X POST -o ../../results/users_update2.json --data @../users/users_update_data2.txt "$myapi"/users
