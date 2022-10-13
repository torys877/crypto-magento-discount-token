# Crypto MagentoToken Magento 2 Extension

Magento 2 module allows to use ERC20 tokens as discount

## Description

Extension allows to collect and use tokens, similar to customer balance functional. Tokens are charged after order placement and customer can use this balance as discount for new orders.

Additionally, customer can claim his tokens from magento store to own metamask wallet and use these tokens in another magento store.

Extension allows to implement complex discount or partnership program between magento stores.

This functional uses signed messages to sign claiming requests from store side and use signed message on customer side for claiming tokens. It means store owner does not need pay any GAS for sending tokens to customer. Customer pays all network taxes (gas) when use signed message to claim tokens in customer area.

## Features

1) Collect 'local discount token' inside magento store for order
2) Use 'discount token' for new order to get discount
3) Customer can send withdraw request for claiming tokens from customer account
4) Store owner can sign customer's request using own metamask account
5) Customer can claim token in own account using signed message

## Installation

To install module you need to add repositories to your `composer.json`:

### Composer installation 

`composer require cryptom2/magento-discount-token:v1.0.0`

### Magento run

```angular2html
php bin/magento setup:upgrade
```

### Settings

![Checkout Payment Page](https://raw.githubusercontent.com/torys877/crypto-magento-discount-token/main/docs/settings.png)

- **Enable** - enable/disable token functional
- **Percent Of Order Grand Total** - how much tokens customer can take from order grand total in percent
- **Multiplier** - multiplier for tokens (ex. if grand total = 50$, percent = 4, multiplier = 100 - customer will have = `50 * 0.04 * 100` = `200` tokens. And for discount it will be `{grand_total} - {tokens}/{multiplier}`)
- **Token Symbol** - Symbol of token used in ERC20 token creation 
- **Token Name** - Token name used in ERC20 token creation
- **Token Address** - address of ERC20 token
- **Smart Contract Address** - address of smart contract that will manage tokens to claim tokens and sign customer requests
- **Smart Contract Abi** - Abi of smart contract

## How to use
### Deploy ERC20 Token and Control Smart Contract

#### Hardhat

To deploy ERC20 token and smart contract you can use Hardhat. Install hardhat and copy folders from hardhat folder in current module to hardhat root directory.

Then run from hardhat root directory:

`npx hardhat node` - to run node

`npx hardhat run --network localhost .\scripts\deploy.js`

#### Remix Ide

To deploy contracts using Remix IDE you need download IDE or use web remix IDE. You need copy all files from directory `hardhat/contracts` and:

1) deploy `MagentoDiscountToken.sol`
2) deploy `ControlContract.sol` using address of MagentoDiscountToken that you should get after `step1`.



***Important:*** 

**you must use the same own address to deploy both contracts. This address should be 'store owner address' and you must have full control for this address.**

### Approve Control Contract Actions

After deploying both contracts and ERC20 token you will have 1 million tokens on your address.

And you need allow control contract to manage your tokens to allow customers claim it. To allow control contract to manage your tokens you need run `approve` function on MagentoDiscountToken.

There is necessary to input Control Smart Contract Address in field `spender` and amount of tokens in field `amount` that smart contract can use.

![Checkout Payment Page](https://raw.githubusercontent.com/torys877/crypto-magento-discount-token/main/docs/approve.png)

**Remember** - you have 1 mln tokens, but totalsupply will show you `1 000 000 * 10**18`. It was made to imitate the same relation as Ether and Wei, so it allows to implement decimals with 18 zero after dot.

So to allow your smart contract use only `2000` of tokens from your account you should input `2000 * 10**18`, final amount will be `2000000000000000000000`.   

## Screenshots

### Menu

Admin panel has 4 menu:
- **Token Balances** - token balance grid associated with customers
- **Token History** - history of all action with token balance (add/claim/refund etc)
- **Token Orders** - orders table with token balance per order
- **Withdraw Requests** - customer token withdraw requests (there is possible to sign message to allow customer claims his token)

![Admin Menu](https://raw.githubusercontent.com/torys877/crypto-magento-discount-token/main/docs/admin_menu.png)

### Withdraw Requests

There is possible to sign request using your metamask. Signing is not use any gas and it is only local operation.
After signing message request will change status and customer can claim tokens using signed message hash.

![Withdraw Request](https://raw.githubusercontent.com/torys877/crypto-magento-discount-token/main/docs/admin_sign.png)


### Customer Token Balance Page
Customer page with balances. Customer can:

- See his token balance
- Send request to claim tokens with amount he would like to claim
- See his requests for claiming tokens with request status. If request has signed by store owner customer sees button `Claim Tokens`
- Claims his tokens by click on button `Claim Tokens` in request grid

![Withdraw Request](https://raw.githubusercontent.com/torys877/crypto-magento-discount-token/main/docs/customer_token_balance.png)

## TODO

- add possibility to top up customer ERC20 token balance in customer account from his metamask wallet
- fix js issue with double click on claim and sign buttons
- feel free to fork and fix/implement anything if you are interested 

## Author

### Ihor Oleksiienko

* [Github](https://github.com/torys877)
* [Linkedin](https://www.linkedin.com/in/igor-alekseyenko-77613726/)
* [Facebook](https://www.facebook.com/torysua/)

## License

Magento ERC20 Discount Token extension for Magento 2 is licensed under the MIT License - see the LICENSE file for details
