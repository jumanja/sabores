#login_api
echo "$myapi"

# Intenta hacer login con admin, se espera login error
curl -o ../../results/login_check_A_error.json --data "id=1&token=updated" "$myapi"/login/check

# Intenta hacer login con presidenta, se espera login error
curl -o ../../results/login_check_P_error.json --data "id=2&token=updated" "$myapi"/login/check

# Intenta hacer login con el logintoken.txt
curl -o ../../results/login_check_S.json --data @/Library/WebServer/Documents/jumanja.net/sisga/tests/api/login/logintoken.txt "$myapi"/login/check
