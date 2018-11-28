#users_api
echo "$myapi"
curl -X PUT -o ../../results/users_token.json --data @../login/logintoken.txt "$myapi"/users/token
