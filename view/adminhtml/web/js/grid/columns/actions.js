/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
define([
    'Magento_Ui/js/grid/columns/actions',
    'Magento_Ui/js/modal/alert',
    'underscore',
    'jquery',
    'mage/translate',
    'web3',
    'Magento_Ui/js/modal/confirm',
], function (Actions, uiAlert, _, $, $t, web3, confirm) {
    'use strict';

    return Actions.extend({
        defaults: {
            ajaxSettings: {
                method: 'POST',
                dataType: 'json'
            },
            ignoreTmpls: {
                fieldAction: true,
                options: true,
                action: true
            },
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
            contract: null
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();

            if (!this.createWeb3()) {
                return;
            }

            this.connectWallet();
        },

        signMessage: function(actionIndex, rowIndex, action) {
            var nonce = action.request_data.nonce,
                request_id = action.request_data.request_id,
                message_hash = action.request_data.message_hash,
                created_at = action.request_data.created_at,
                amount = Math.round(action.request_data.amount),
                recipient_address = action.request_data.recipient_address;

            var hash = web3.utils.soliditySha3(
                recipient_address, amount, nonce, request_id, message_hash, created_at
            );
            var self = this;
            if (!this.getCurrentAccount() || !this.isWalletConnected()) {
                this.createWeb3();
                this.connectWallet();
            }
            this.web3client.eth.personal.sign(
                hash,
                this.getCurrentAccount(),
                function(error, signature) {
                    console.log(error);
                    console.log(signature);

                    $.ajax({
                        type: 'POST',
                        url: action.href,
                        showLoader: true,
                        data: {
                            "signed_message": signature
                        }
                    })
                        .done(function(response) {
                            window.location.reload();
                        });
                }
            );
            console.log(23423);
        },
        getActionHandler: function (action) {
            var index = action.index,
                rowIndex = action.rowIndex;

            return this.applyAction.bind(this, index, rowIndex);

            // return this.signMessage.bind(this, index, rowIndex, action);
        },

        applyAction: function (actionIndex, rowIndex) {
            console.log(888888999999);
            var action = this.getAction(rowIndex, actionIndex),
                callback = this.signMessage.bind(this, actionIndex, rowIndex, action);

            this._confirm(action, callback);

            return this;
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
        }
    });
});
