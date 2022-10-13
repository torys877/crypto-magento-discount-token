// SPDX-License-Identifier: MIT
pragma solidity ^0.8.17;

import "./@openzeppelin/contracts/token/ERC20/IERC20.sol";
import "./MagentoDiscountToken.sol";

contract ControlContract {

    MagentoDiscountToken public MDTtoken;
    address public owner;

    mapping(address => mapping(uint => bool)) public nonceMap;

    constructor(
        address _token
    ) {
        MDTtoken = MagentoDiscountToken(_token);
        owner = msg.sender;
    }

    function claimTokens(
        address _recipient_address,
        uint _amount,
        uint _nonce,
        uint _request_id,
        string calldata _message_hash,
        string calldata _created_at,
        bytes memory _signature
    ) public {
        require(!nonceMap[msg.sender][_nonce], "Tokens Already claimed");

        require(
            verify(
                _recipient_address,
                _amount,
                _nonce,
                _request_id,
                _message_hash,
                _created_at,
                _signature
            ),
            "Signature is not verified"
        );

        _safeTransferFrom(owner, msg.sender, _amount * 10 ** MDTtoken.decimals());
        nonceMap[msg.sender][_nonce] = true;
    }

    function _safeTransferFrom(
        address sender,
        address recipient,
        uint amount
    ) private {
        bool sent = MDTtoken.transferFrom(sender, recipient, amount);
        require(sent, "Token transfer failed");
    }

    function getMessageHash(
        address _recipient_address,
        uint _amount,
        uint _nonce,
        uint _request_id,
        string calldata _message_hash,
        string calldata _created_at
    ) public pure returns (bytes32) {
        return keccak256(abi.encodePacked(_recipient_address, _amount, _nonce, _request_id, _message_hash, _created_at));
    }

    function getEthSignedMessageHash(bytes32 _messageHash)
    public
    pure
    returns (bytes32)
    {
        return
        keccak256(
            abi.encodePacked("\x19Ethereum Signed Message:\n32", _messageHash)
        );
    }

    function verify(
        address _recipient_address,
        uint _amount,
        uint _nonce,
        uint _request_id,
        string calldata _message_hash,
        string calldata _created_at,
        bytes memory _signature
    ) public view returns (bool) {
        bytes32 messageHash = getMessageHash(_recipient_address, _amount, _nonce, _request_id, _message_hash, _created_at);
        bytes32 ethSignedMessageHash = getEthSignedMessageHash(messageHash);

        return recoverSigner(ethSignedMessageHash, _signature) == owner;
    }

    function recoverSigner(bytes32 _ethSignedMessageHash, bytes memory _signature)
    public
    pure
    returns (address)
    {
        (bytes32 r, bytes32 s, uint8 v) = splitSignature(_signature);

        return ecrecover(_ethSignedMessageHash, v, r, s);
    }

    function splitSignature(bytes memory sig)
    public
    pure
    returns (
        bytes32 r,
        bytes32 s,
        uint8 v
    )
    {
        require(sig.length == 65, "invalid signature length");

        assembly {
        // first 32 bytes, after the length prefix
            r := mload(add(sig, 32))
        // second 32 bytes
            s := mload(add(sig, 64))
        // final byte (first byte of the next 32 bytes)
            v := byte(0, mload(add(sig, 96)))
        }
    }
}
