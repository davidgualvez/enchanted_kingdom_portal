@extends('layouts.customers.main')
@section('title','Purchase History')

@section('js')
<script src="/js/plugins/qrcode.js"></script>
<script src="/js/pages/purchase_history.js"></script>
@endsection

@section('css')
<style>
 .box{
     border: 1px solid gray;
 }
</style>
@endsection

@section('content') 

<div class="ui container">
    <div class="ui stackable column grid"> 
        <div class="column">
            <h3 class="ui header box">
                Purchased History
                <div class="sub header">Summary of all Purchases.</div>
            </h3>  
            {{-- pagination content --}}
            <div class="step1" id="purchaseHistory" style="padding: 25px 0xpx 0px 0px;"> 
                {{-- <div class="ui vertical segment">
                    <div style="padding: 10px;">
                        <div class="ui middle aligned divided list">
                            <div class="item">
                                <div class="right floated content">
                                    <strong>xx-xx-xxxx</strong>
                                </div> 
                                <div class="content">
                                    Date/Time
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>WEB/POS</strong>
                                </div> 
                                <div class="content">
                                    Type
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Total Amount
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Total Discount
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Net Amount
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Earned Points
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Used Wallet
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Used Points
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Wallet Balance
                                </div>
                            </div> 
                            <div class="item">
                                <div class="right floated content">
                                    <strong>0.00</strong>
                                </div> 
                                <div class="content">
                                    Points Balance
                                </div>
                            </div> 
                        </div>
                        <div class="ui accordion">
                            <div class="title">
                            <i class="dropdown icon"></i>
                            Detail's
                            </div>
                            <div class="content">
                            <p class="transition" style="display: block !important;"> 
                                <table class="ui small celled table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Srp</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Discounted Amount</th>
                                        <th>Valid Until</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>James</td>
                                        <td>24</td>
                                        <td>Engineer</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> 
                                    </tbody>
                                </table> 
                            </p>
                            </div> 
                        </div>
                    </div> 
                </div>  --}} 
            </div>
            {{-- end of pagination content --}}
            {{-- pagination buttons --}} 
            <div class="tiny ui buttons">
                <button class="ui button" id="prev_page_url_purchase_history">
                <i class="angle left icon"></i>
                Prev
                </button> 
                <button class="ui button" id="next_page_url_purchase_history">
                <i class="angle right icon"></i>
                Next
                </button>
            </div> 
            <br>
            {{-- end of pagination --}} 
        </div> 
    </div>
</div>
@endsection