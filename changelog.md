# Change Log

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