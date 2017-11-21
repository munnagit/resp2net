<!DOCTYPE html>
<html>

 <head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Self Banking</title>

	<link rel="stylesheet" href="../assets/demo.css">
    <link rel="stylesheet" href="../assets/form-basic.css">

    <!-- Required CSS for table -->
    <!--<link rel="stylesheet" href="assets/normalize.css"> -->
    <link rel="stylesheet" href="../assets/style.css">
    <script src='http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js'></script>



 </head>

 <body>
    <header>
        <center><h1>SBI Self Banking</h1></center>
        <!-- <a href="http://tutorialzine.com/2015/07/freebie-7-clean-and-responsive-forms/">Download</a> -->
    </header>

    <ul>
        <li><a href="../index.php">New Client</a></li>
        <li><a href="index.php" class="active">Self Banking</a></li>
        <li><a href="../form-search.php">Search</a></li>
    </ul>


   <div class="main-content">

    <?php
    //phpcode responsibele for displaying tbl_cash row
        include("../connection.php");
        $sql="SELECT * FROM tbl_cash";
        $res=$con->query($sql);
        $nrows=$res->num_rows;
        echo "<br>";
        echo "<form action = 'banking/index.php' method = 'POST' class='form-horizontal'>";
        print "<table class=\"responstable\">\n";
        print "         <tr>\n";
        print "            <th data-th=\"Order Details\"><span>Cash Balance</span></th>\n";
        print "            <th><span><center>Account Balance</center></span></th>\n";
        print "         </tr>";
        if ($nrows > 0) {
            while ($get_column=$res->fetch_assoc()) {
                echo "<td>". $get_column['scih']."</td>";
                echo "<td><center>". $get_column['scab']."</center></td>";
                echo "</tr>";
            }
        }
        echo "</table>
            <br><br>
          </form>";
        mysqli_close($con);

    //phpcode responsibele for inserting into tbl_sbitrans
    if (isset($_POST["opn"])) {
        $opn= $_POST['opn'];
        $amt= $_POST['amt'];
        $cmt= $_POST['cmt'];
        //echo ": ". $_POST['opn']. "<br />"; //Result Check
          
        //DB Connectivity & Insert Query
        include("../connection.php");
            
        $sql = "INSERT INTO tbl_selftrans ". "(opn, amt, cmt)". "VALUES('$opn','$amt','$cmt')";
                 
        if ($con->query($sql) === true) {
            //echo "New record created successfully"; echo "<br />";
            echo "<div class='alert success'>
                <span class='closebtn'>&times;</span>
                <strong>Success!</strong> Data Inserted Successfully !!!
                </div>";
                if($opn=="Deposit")
                {
                    $sql = "UPDATE tbl_cash SET scih=scih+'$amt'";
                    $con->query($sql);

                    $sql = "UPDATE tbl_cash SET scab=scab+'$amt'";
                    $con->query($sql);
                }
                else
                {
                    $sql = "UPDATE tbl_cash SET scih=scih-'$amt'";
                    $con->query($sql);

                    $sql = "UPDATE tbl_cash SET scab=scab-'$amt'";
                    $con->query($sql);
                }
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
        $con->close();
    }
        ?> 

        <!-- You only need this form and the form-basic.css -->

        <form action = "<?php $_PHP_SELF ?>" method = "POST" class="form-basic" method="post" action="#">

            <div class="form-title-row">
                <h1>Self Transaction Entry</h1>
            </div>

            <div class="form-row">
                <label>
                    <span>Operation</span>
                    <select name="opn" style="padding-right: 143px;">
                        <option value="Deposit">Deposit</option>
                        <option value="Withdrawal">Withdrawal</option>
                    </select>
                </label>
            </div>

            <div class="form-row">
                <label>
                    <span>Amount</span>
                    <input type="text" name="amt">
                </label>
            </div>

            <div class="form-row">
                    <label>
                        <span>Comment</span>
                        <input type="text" name="cmt">
                    </label>
            </div>

            <div class="form-row"> 
                <button type="submit">Enter</button>
            </div>

        </form>

    </div>

    </body>

</html>