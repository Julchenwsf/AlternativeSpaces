<?php
            include 'DBConnection.php'; /** calling of connection.php that has the connection code **/

            $User_ID = $_GET['id']; /** get the user ID **/

            if( isset( $_POST['update'] ) ): /** A trigger that execute after clicking the submit     button **/

                /*** Putting all the data from text into variables **/

                $fname = $_POST['fname'];
                $mname = $_POST['mname'];
                $lname = $_POST['lname'];
                $addr = $_POST['addr'];
                $gender = $_POST['gender'];


                mysql_query("UPDATE user_record SET fname = '$fname', mname = '$mname', lname = '$lname', addr = '$addr', gender = '$gender' WHERE user_ID = '$ID'")
                            or die(mysql_error()); /*** execute the insert sql code **/

                echo "<div class='alert alert-info'> Successfully Updated. </div>"; /** success message **/

            endif;

            $result = mysql_query("SELECT * FROM users_record WHERE user_id='$ID'");

            $data = mysql_fetch_object( $result );

        ?>

        <form action="" method="POST">
            <label> Full Name: </label>
                <input type="text" value="<?php echo $data->fname ?>" class="input-medium" name="fname" />
                <input type="text" value="<?php echo $data->mname ?>" class="input-medium" name="mname" />
                <input type="text" value="<?php echo $data->lname ?>" class="input-medium" name="lname"/>
            <label> Address: </label>
                <textarea class="span7" name="addr"><?php echo $data->addr ?></textarea>
            <label> Gender: </label>
                <select class="span2" name="gender">
                    <?php if($data->gender=='Male'): ?>
                        <option value="Male" selected>Male</option>
                        <option value="Female">Female</option>
                    <?php else: ?>
                        <option value="Male">Male</option>
                        <option value="Female" selected>Female</option>
                    <?php endif; ?>

            <br />
            <input type="submit" name="update" value="Update" class="btn btn-info" />

        </form>