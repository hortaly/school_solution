<?php
include("db_conn.php");
include("session.php");

$receipt_no = mysqli_real_escape_string($conn, $_REQUEST['receipt_no'] ?? '');
$payment_id = mysqli_real_escape_string($conn, $_REQUEST['payment_id'] ?? '');
$trans_id = mysqli_real_escape_string($conn, $_REQUEST['transaction_id'] ?? '');
$student_uin = mysqli_real_escape_string($conn, $_REQUEST['student_uin'] ?? '');
$fee_id = mysqli_real_escape_string($conn, $_REQUEST['fee_id'] ?? '');

if($receipt_no != ''){
    $get = mysqli_query($conn,"SELECT * FROM payments WHERE receipt_no='$receipt_no' LIMIT 1");
} elseif($payment_id != ''){
    $get = mysqli_query($conn,"SELECT * FROM payments WHERE payment_id='$payment_id' LIMIT 1");
} elseif($trans_id != ''){
    $get = mysqli_query($conn,"SELECT * FROM payments WHERE transaction_id='$trans_id' LIMIT 1");
} elseif($student_uin != '' && $fee_id != ''){
    $get = mysqli_query($conn,"SELECT * FROM payments WHERE student_uin='$student_uin' AND fee_id='$fee_id' ORDER BY id DESC LIMIT 1");
} else { die("<center><h3>No Receipt ID</h3></center>"); }

if(mysqli_num_rows($get)==0){ die("<center><h3>Receipt not found</h3></center>"); }

$pay = mysqli_fetch_assoc($get);
$stu = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM students WHERE student_uin='".$pay['student_uin']."' LIMIT 1"));
$par = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM parents WHERE parent_uin='".$stu['parent_uin']."' LIMIT 1"));
$fee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM fees WHERE fee_id='".$pay['fee_id']."' LIMIT 1"));
$led = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM ledger WHERE student_uin='".$pay['student_uin']."' AND fee_id='".$pay['fee_id']."' ORDER BY id DESC LIMIT 1"));
$send_date = date("d M, Y h:i A", strtotime($pay['created_at']));
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JOSHTECH - <?php echo $pay['receipt_no']; ?></title>
<style>
body{font-family:'Segoe UI', Arial; background:#f6f7fb; margin:0; padding:20px; display:flex; justify-content:center;}
.receipt-box{
    width:210mm; height:297mm; overflow:hidden; background:#fff;
    background-image:url('receipt_picture/school_receipt.jpg');
    background-size:100% 100%; background-repeat:no-repeat; background-position:center;
    margin:30px auto; padding:35mm 20mm 30mm 20mm;
    box-shadow:0 10px 35px rgba(0,0,0,.15); position:relative;
}
.header{text-align:center; border-bottom:2px solid #6259ca; padding-bottom:15px; margin-bottom:20px;}
.details table{width:100%; border-collapse:collapse;}
.details td{padding:12px; border:1px solid #eee; font-size:14px; word-break:break-all;}
.details td:first-child{font-weight:700; background:rgba(255,255,255,.7); width:220px;}
.footer{display:flex; justify-content:space-between; margin-top:40px;}
.sign{margin-top:60px; text-align:center;} .sign span{border-top:1px solid #000; padding-top:5px; display:inline-block; width:200px; font-size:12px;}
.print-btn{text-align:center; margin-top:20px; display:flex; gap:10px; justify-content:center; position:fixed; bottom:15px; left:50%; transform:translateX(-50%); background:#fff; padding:10px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,.2); z-index:999; width:95%; max-width:500px;}
.print-btn button{padding:11px 22px; border-radius:10px; font-weight:700; border:none; cursor:pointer; flex:1;}
.btn-black{background:#1a1a2e; color:#fff} .btn-primary{background:#6259ca; color:#fff}
@media screen and (max-width:768px){
    body{padding:0; background:#fff;}
    .receipt-box{width:100vw; height:100vh; margin:0; padding:28mm 6mm 20mm 6mm; box-shadow:none; overflow:hidden;}
}
@media print{.print-btn{display:none} body{background:#fff; padding:0;} .receipt-box{box-shadow:none; border:none; width:210mm; height:297mm; margin:0;}}
</style>
</head>
<body>
<div class="receipt-box" id="receiptContent">
    <div class="header"><h1 style="margin:0; font-size:26px;"></h1><p>Official Payment Receipt</p></div>
    <div style="text-align:center; font-weight:800; margin:15px 0; color:#6259ca;">PAYMENT RECEIPT #<?php echo $pay['receipt_no']; ?></div>
    <div class="details">
        <table>
            <tr><td>Transaction ID</td><td><b style="font-family:monospace;"><?php echo $pay['transaction_id']; ?></b></td></tr>
            <tr><td>Payment ID</td><td><?php echo $pay['payment_id']; ?></td></tr>
            <tr><td>Receipt No</td><td><b><?php echo $pay['receipt_no']; ?></b></td></tr>
            <tr><td>Student Name</td><td><?php echo $stu['student_name'] ?? $pay['student_uin']; ?> (<?php echo $pay['student_uin']; ?>)</td></tr>
            <tr><td>Parent</td><td><?php echo $par['parent_name'] ?? ''; ?></td></tr>
            <tr><td>Fee Description</td><td><?php echo $pay['description']; ?></td></tr>
            <tr><td>Amount Paid</td><td><b style="color:#059669;">₦<?php echo number_format($pay['amount_paid'],2); ?></b></td></tr>
            <tr><td>Balance After</td><td>₦<?php echo number_format($led['balance_after'] ?? 0,2); ?></td></tr>
            <tr><td>Method</td><td><?php echo ucfirst($pay['payment_method']); ?></td></tr>
            <tr><td>Date</td><td><?php echo $send_date; ?></td></tr>
            <tr><td>Status</td><td style="color:#059669; font-weight:bold;">SUCCESS</td></tr>
        </table>
    </div>
    <div class="footer"><div class="sign"><span>Parent Signature</span></div><div class="sign"><span>Bursar Signature</span></div></div>
</div>
<div class="print-btn">
    <button class="btn-black" onclick="location.href='print_receipt.php?receipt_no=<?php echo $pay['receipt_no']; ?>&format=a4'">🖨 Print / Download</button>
    <button class="btn-primary" onclick="location.href='view_parent.php?parent_uin=<?php echo $stu['parent_uin']; ?>'">← Back</button>
</div>
</body>
</html>