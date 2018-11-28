#users_api
echo "$myapi"

# Genera los parámetros para cada llamado insertando primero el token obtenido en la sesión
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_S2_params.txt > ../users/users_add_S2_data.txt

# Intenta crear un usuario para cada tipo, todos se espera ok
curl -o ../../results/users_add_S2.json --data @../users/users_add_S2_data.txt "$myapi"/users

