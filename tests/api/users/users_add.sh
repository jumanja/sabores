#users_api
echo "$myapi"

# Genera los parámetros para cada llamado insertando primero el token obtenido en la sesión
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_A_params.txt > ../users/users_add_A_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_P_params.txt > ../users/users_add_P_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_S_params.txt > ../users/users_add_S_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_T_params.txt > ../users/users_add_T_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_F_params.txt > ../users/users_add_F_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_E_params.txt > ../users/users_add_E_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_R_params.txt > ../users/users_add_R_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../users/users_add_N_params.txt > ../users/users_add_N_data.txt


# Intenta crear un usuario para cada tipo, todos se espera ok
curl -o ../../results/users_add_A.json --data @../users/users_add_A_data.txt "$myapi"/users
curl -o ../../results/users_add_P.json --data @../users/users_add_P_data.txt "$myapi"/users
curl -o ../../results/users_add_S.json --data @../users/users_add_S_data.txt "$myapi"/users
curl -o ../../results/users_add_T.json --data @../users/users_add_T_data.txt "$myapi"/users
curl -o ../../results/users_add_F.json --data @../users/users_add_F_data.txt "$myapi"/users
curl -o ../../results/users_add_E.json --data @../users/users_add_E_data.txt "$myapi"/users
curl -o ../../results/users_add_R.json --data @../users/users_add_R_data.txt "$myapi"/users
curl -o ../../results/users_add_N.json --data @../users/users_add_N_data.txt "$myapi"/users

