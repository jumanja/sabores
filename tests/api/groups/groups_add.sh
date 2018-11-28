#users_api
echo "$myapi"

# Genera los parámetros para cada llamado insertando primero el token obtenido en la sesión
cat ../login/logintoken.txt ../ampersand.txt ../groups/groups_add_demo_params.txt > ../groups/groups_add_demo_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../groups/groups_add_demo_params2.txt > ../groups/groups_add_demo_data2.txt

# Intenta crear una grupo, todos se espera ok
curl -o ../../results/groups_add_A.json --data @../groups/groups_add_demo_data.txt "$myapi"/groups
curl -o ../../results/groups_add_B.json --data @../groups/groups_add_demo_data2.txt "$myapi"/groups
