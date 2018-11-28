#users_api
echo "$myapi"

# Genera los parámetros para cada llamado insertando primero el token obtenido en la sesión
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_jumanja_params.txt > ../users/users_add_jumanja_data.txt


# Intenta crear un usuario para cada tipo, todos se espera ok
curl -o ../../results/users_add_jumanja.json --data @../users/users_add_jumanja_data.txt "$myapi"/users
