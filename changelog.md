# Change Log
### Feb 20, 2019
- added jobs table with corresping columns
    - id | bigint | PK (auto increment)
    - queue | nvarchar(255)
    - payload | nvarchar(MAX)
    - attempts | tinyint
    - reserved_at | int
    - available_at | int
    - created_at | int

### Jan 27, 2019
- Added Kitchen functionality

### Jan 26, 2019 
- Remove the added is_component_of_pid(int) column in HODB carts table.
- Added new Table named carts_component

### Jan 25, 2019
- HODB carts Column changes
    - Added is_component_of_pid(int) column
    
### Jan 24, 2019
- Added TurnSite log for reporting
- TurnSite Column Changes
    - TYPENO(decimal, 1) to TYPENO(int)
- Removed Order Functionalities

### Jan 23, 2019
- Remove Preview Receipt Button and Changed E-wallet message to Load-Wallet

### Jan 21, 2019
- Added computed data into transaction receipt for record purposes in Transaction History page.
- Change the Category casing from Camel case to Upper case 

### Jan 20, 2019
- Added HODB branch_last_issued_numbers Table column
    - Column name   |   DataType
    - invoice_no    |   decimal(18,0)
    - none_invoice_no   |   decimal(18,0)
    - invoice_max_no    |   decimal(18,0)
    - invoice_max_counter   |   int
    - transaction_no    |   decimal(18,0)
- Added HODB Customers Table column
    - Column name   |   DataType
    - SCPWD_ID  |   varchar(50)
- Added HODB purchase_transactions Table
- Added HODB purchase_transaction_details Table
- Added OR Button in Purchase History with Preview and Download PDF Functionality for WEB Transaction only

### Jan 19, 2019
- Removed tutorial in everypage.
- Change the mobile number entry field to 9xxxxxxxxx format for syncronization in other application.
- Added Email notification after signup and forgot password.

### Dec 29, 2018
- Added back to home page button in login and signup page.
- Fixed the checkout/order when the items in cart is empty

### Dec 28, 2018
- Exclude the some Sitepart from the list of master product when the PrePartNo is set to 1
- Added the identifier[MSGROUP][0|1] of product if food. Then include it upon saving to Sales order details