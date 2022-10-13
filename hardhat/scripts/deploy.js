const hre = require("hardhat");

async function main() {
  const MDT = await hre.ethers.getContractFactory("MagentoDiscountToken");
  const MDTdeployed = await MDT.deploy();
  await MDTdeployed.deployed();

  const ControlContract = await hre.ethers.getContractFactory("ControlContract");
  const ControlContractDeployed = await ControlContract.deploy(MDTdeployed.address);
  await ControlContractDeployed.deployed();

  console.log(
    `Deployed MDT address ${MDTdeployed.address}`
  );

  console.log(
    `Deployed Control Contract address ${ControlContractDeployed.address}`
  );
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
