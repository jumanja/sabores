#servs_api
echo "$myapi"

# Genera los parámetros para cada llamado insertando primero el token obtenido en la sesión
# cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_A_params.txt > ../servs/servs_add_A_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_P_params.txt > ../servs/servs_add_P_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_S_params.txt > ../servs/servs_add_S_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_T_params.txt > ../servs/servs_add_T_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_F_params.txt > ../servs/servs_add_F_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_E_params.txt > ../servs/servs_add_E_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_R_params.txt > ../servs/servs_add_R_data.txt
cat ../login/logintoken.txt ../ampersand.txt ../servs/servs_add_N_params.txt > ../servs/servs_add_N_data.txt


# Intenta crear un usuario para cada tipo, todos se espera ok
# curl -o ../../results/servs_add_A.json --data @../servs/servs_add_A_data.txt "$myapi"/servs
curl -o ../../results/servs_add_P.json --data @../servs/servs_add_P_data.txt "$myapi"/servs
curl -o ../../results/servs_add_S.json --data @../servs/servs_add_S_data.txt "$myapi"/servs
curl -o ../../results/servs_add_T.json --data @../servs/servs_add_T_data.txt "$myapi"/servs
curl -o ../../results/servs_add_F.json --data @../servs/servs_add_F_data.txt "$myapi"/servs
curl -o ../../results/servs_add_E.json --data @../servs/servs_add_E_data.txt "$myapi"/servs
curl -o ../../results/servs_add_R.json --data @../servs/servs_add_R_data.txt "$myapi"/servs
curl -o ../../results/servs_add_N.json --data @../servs/servs_add_N_data.txt "$myapi"/servs

