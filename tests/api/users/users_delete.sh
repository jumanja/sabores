#users_api
echo "$myapi"

# Intenta crear los datos para el update estado=R, insertando primero los datos del token de la sesión
cat ../login/logintoken.txt ../ampersand.txt ../users/users_delete_params.txt > ../users/users_delete_data.txt


# Intenta cambiar el estado a R, se espera ok
curl -X PUT -o ../../results/users_delete.json --data @../users/users_delete_data.txt "$myapi"/users/delete
