/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
define([
    'uiComponent',
    'jquery',
    'web3'
], function (Component, $, web3) {
    'use strict';

    return Component.extend({
        defaults: {
            claimRequestUrl: null,
            merchantAddress: null,
            networkVersion: null,
            givenProvider: null,
            orderAmount: null,
            orderHash: null,
            addTxUrl: null,
            thCheckAndConfirmUrl: null,
            successUrl: null,
            web3client: null,
            requestIntervalSeconds: null,
            accounts: [],
            contract: null,
            contractAddress: null,
            contractAbi: null,
            tokenAddress: null,
            claimTokensUrl: null
        },
        /** @inheritdoc */
        initialize: function () {
            this._super();

            if (!this.createWeb3()) {
                return;
            }

            this.connectWallet();
        },
        claimRequest: function(self, obj) {
            var kk = $(obj.currentTarget).data('customdat');
            if (!this.getCurrentAccount() || !this.isWalletConnected()) {
                this.createWeb3();
                this.connectWallet();
            }

            if (!this.getCurrentAccount()) {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: self.claimRequestUrl,
                showLoader: true,
                data: {
                    "request_amount": $('#request_amount').val(),
                    "recipient_address": this.getCurrentAccount()
                }
            })
            .done(function(response) {
                self.showMessage(response.message);
                if (!response.result) {
                    return;
                }

                window.location.reload();
            })
            .fail(function(result){
                self.showMessage('Sorry, there was a problem saving the settings.');
            });
        },
        claimTokens: function(self, obj) {
            var self = this;
            if (!this.getCurrentAccount() || !this.isWalletConnected()) {
                this.createWeb3();
                this.connectWallet();
            }

            this.loadContract();

            // function claimTokens(
            //     address _recipient_address,
            //     uint _amount,
            //     uint _nonce,
            //     uint _request_id,
            //     string calldata _message_hash,
            //     string calldata _created_at,
            //     bytes memory _signature
            this.contract.methods
                .claimTokens(
                    self.getCurrentAccount(),
                    parseInt(obj.currentTarget.dataset.amount),
                    parseInt(obj.currentTarget.dataset.nonce),
                    parseInt(obj.currentTarget.dataset.requestid),
                    obj.currentTarget.dataset.messagehash,
                    obj.currentTarget.dataset.createdat,
                    obj.currentTarget.dataset.signature
                ).send(
                    {
                        from: self.getCurrentAccount()
                    }
                )
                .then(function(responseObj) {
                    self.showMessage('Transaction is sent. Txh = ' + responseObj.transactionHash);
                    console.log(responseObj);
                    $('#approve').show();
                    $('#claim').show();

                    $.ajax({
                        type: 'POST',
                        url: self.claimTokensUrl,
                        showLoader: true,
                        data: {
                            "request_id": obj.currentTarget.dataset.requestid,
                        }
                    })
                        .done(function(response) {
                            self.showMessage(response.message);
                            if (!response.result) {
                                return;
                            }
                        })
                        .fail(function(result){
                            self.showMessage('Sorry, there was a problem saving the settings.');
                        });
                })
                .catch(function(errObj) {
                    self.showMessage('Transaction is declined by client. ' + errObj.code + ': ' + errObj.message);
                })
            ;
        },
        createWeb3: function() {
            this.givenProvider = web3.givenProvider;
            if (
                this.givenProvider &&
                typeof this.givenProvider != 'undefined'
            ) {
                this.web3client = new web3(web3.givenProvider); //metamask provider, or custom network url, ex:'ws://some.local-or-remote.node:8546'
            } else {
                this.showMessage('Metamask is not authorized or not installed.');
                return false;
            }
        },
        /** connect metamask wallet to website **/
        connectWallet: function() {
            if (!this.isWeb3()) {
                return;
            }
            let self = this;
            this.web3client.eth.requestAccounts().then(
                function(accs) {
                    self.accounts = accs;
                    if (accs.length) {
                        $('#connect_wallet').hide();
                        $('#pay').show();
                        self.accounts.forEach(function(element){
                            if (!$("#accounts option[value='" + element + "']").length) {
                                $('#accounts').append($('<option>', {
                                    value: element,
                                    text: element
                                }));
                            }
                        });
                    }
                }
            );
        },
        //create contract object in web3js as js object
        loadContract:  function() {
            var self = this;
            this.contract = new this.web3client.eth
                .Contract(
                    JSON.parse(self.contractAbi), //contract ABI
                    self.contractAddress //contract address
                );
            this.showMessage('Contract is loaded');
        },
        /** check is provider exist **/
        isWeb3: function() {
            if (!this.web3client) {
                if (this.createWeb3()) {
                    return true;
                }

                return false;
            }

            return true;
        },
        /** check is wallet connected to website **/
        isWalletConnected: function() {
            if (!this.isWeb3()) {
                return;
            }

            this.connectWallet();

            return this.accounts[0] ? true : false;
        },
        /** get current account **/
        getCurrentAccount: function() {
            if (this.isWalletConnected()) {
                return this.accounts[0];
            }

            return false;
        },

        showMessage: function(message) {
            $('.message').html(message);
            $('.message').show();
        },
    });
});
