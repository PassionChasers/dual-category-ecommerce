<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            box-sizing: border-box;
            font-family: monospace;
        }
        body{
            padding: 10px;
        }
        /* #form-box{
            border: 2px solid black;
        } */
        /* receipt css */
        #receipt{
            width: 80mm;
            background: #fff;
            padding:10px;
            border: 1px solid black;
            
        }
        .center{
            display: flex;
            justify-content: center;
        }
        #clientName{
            font-size: small;
            /* font-weight: bold; */
        }
        .line{
            border-bottom: 1px dashed black;
            margin: 6px;
        }
        table{
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }
        table th,
        table td{
            border: 1px solid black;
            padding: 3px;
            text-align: center;
        }
        .tableHeadSection{
            font-weight: bold;
            font-size: medium;
        }
        /* .itemEnd{
            display: flex;
            justify-content: end;
        } */
    
        @media print{
            body{
                visibility: hidden;
            }
            #receipt{
                visibility: visible;
                position: absolute;
                left: 0;
                top:0;
                width: 3in;
                font-size: small;
                font-family: monospace;
            }
            /* #receipt button{
                display: none;
            } */
        }

    </style>
</head>
<body onload="window.print()">

    <!-- receipt -->
    <div id="receipt" >
      
        <div id="header"  style="text-align: center;">
            
            <strong>SD MART</strong>  <br>
            BIRATNAGAR, NEPAL <br>
            PHONE : +977 980000000 <br>
            PAN : 33AAAGP0685F1ZH <br>
        </div> <br>
        <strong class="center">Retail Invoice</strong> <br>
        <div > Customer Name: <span id="clientName">{{ $order->customer->Name ?? 'N/A' }}</span></div>
        <div>Customer Phone No: <span id="billNo">{{ $order->customer->user->Phone ?? 'N/A' }}</span></div>
        <div>Bill No: <span id="billNo">{{ $order->OrderNumber }}</span></div>
        <div>payment-mode: <span id="paymentMode">cash</span></div>
        <div style="display: flex; justify-content: space-between;">
             <div>
                 <div>Date : <span id="current-Date" >{{ date('Y-m-d', strtotime($order->CreatedAt)) }}</span></div>
                 <div>Time : <span id="current-Time" >{{ date('H:i:s', strtotime($order->CreatedAt)) }}</span></div>
             </div>
        </div>
        <div class="line"></div>
        <table>
            <thead>
                <tr>
                    <th class="tableHeadSection">Items</th>
                    <th class="tableHeadSection">Qty</th>
                    <th class="tableHeadSection">Rate</th>
                    <th class="tableHeadSection">Amount</th>
                </tr>
            </thead>
            <tbody id="receipt-items">
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->medicine->Name ?? $item->food->Name }}</td>
                    <td>{{ $item->Quantity ?? 'N/A' }}</td>
                    <td>{{ number_format((float)$item->UnitPriceAtOrder, 2) }}</td>
                    <td>{{ number_format((float)$item->UnitPriceAtOrder * (float)$item->Quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="line"></div>
        <div>Sub Total : {{ number_format($order->TotalAmount, 2) ?? 'N/A' }}</div><br>
        <div>Discount : 0.00</div><br>
        <div>Total Amount : {{ number_format($order->TotalAmount, 2) ?? 'N/A' }}</div>

        <!-- print button -->
        {{-- <div class="printButton">
            <button onclick="window.print()">print</button>
        </div> --}}

    </div>
</body>
</html>