USE [EKHODB]
GO
/****** Object:  Table [dbo].[purchase_transaction_details]    Script Date: 4/10/2019 1:54:26 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[purchase_transaction_details](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[purchase_transaction_id] [int] NULL,
	[sequence] [int] NULL,
	[sitepart_id] [int] NULL,
	[product_name] [varchar](50) NULL,
	[cost] [decimal](18, 4) NULL,
	[retail_price] [decimal](18, 4) NULL,
	[qty] [decimal](18, 2) NULL,
	[is_postmix] [int] NULL,
	[is_vat] [int] NULL,
	[is_food] [int] NULL,
	[vatable_sales] [decimal](18, 4) NULL,
	[vat_exempt_sales] [decimal](18, 4) NULL,
	[vat_zerorated_sales] [decimal](18, 4) NULL,
	[zerorated_vat_amount] [decimal](18, 4) NULL,
	[vat_amount] [decimal](18, 4) NULL,
	[r_vat_amount] [decimal](18, 4) NULL,
	[is_admission] [decimal](18, 4) NULL,
	[admission_sales] [decimal](18, 4) NULL,
	[amusement_tax_exempt_sales] [decimal](18, 4) NULL,
	[amusement_zerorated_sales] [decimal](18, 4) NULL,
	[amusement_zerorated_amount] [decimal](18, 4) NULL,
	[amusement_tax_amount] [decimal](18, 4) NULL,
	[r_amusement_tax_amount] [decimal](18, 4) NULL,
	[gross_amount] [decimal](18, 4) NULL,
	[promo_code] [decimal](18, 4) NULL,
	[discount_rate] [decimal](18, 4) NULL,
	[discount_value] [decimal](18, 4) NULL,
	[discount_amount] [decimal](18, 4) NULL,
	[net_amount] [decimal](18, 4) NULL,
	[created_at] [datetime] NULL,
 CONSTRAINT [PK_purchase_transaction_details] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[purchase_transactions]    Script Date: 4/10/2019 1:54:26 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[purchase_transactions](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[sales_order_id] [int] NULL,
	[created_at] [datetime] NULL,
	[transaction_no] [int] NULL,
	[invoice_no] [int] NULL,
	[customer_id] [int] NULL,
	[customer_code] [varchar](50) NULL,
	[customer_name] [varchar](50) NULL,
	[customer_type] [int] NULL,
	[customer_id_number] [varchar](50) NULL,
	[customer_address] [text] NULL,
	[gross_total] [decimal](18, 2) NULL,
	[scpwd_discount] [decimal](18, 2) NULL,
	[net_total] [decimal](18, 2) NULL,
	[vatable_sales_total] [decimal](18, 4) NULL,
	[vat_exempt_sales_total] [decimal](18, 4) NULL,
	[vat_zerorated_sales_total] [decimal](18, 4) NULL,
	[vat_amount_total] [decimal](18, 4) NULL,
	[r_vat_amount_total] [decimal](18, 4) NULL,
	[admission_sales_total] [decimal](18, 4) NULL,
	[amusement_tax_exempt_sales_total] [decimal](18, 4) NULL,
	[amusement_zerorated_sales_total] [decimal](18, 4) NULL,
	[amusement_tax_amount_total] [decimal](18, 4) NULL,
	[r_amusement_tax_amount_total] [decimal](18, 4) NULL,
 CONSTRAINT [PK_purchase_transactions] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
