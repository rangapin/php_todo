<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo List</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="main-section">
        <h1 m-4 ml-1 >Your Friendly Todo List</h1>
        <div class="add-section">

            <form action="app/add.php" method="POST" autocomplete="off">

                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>

                    <input type="text" name="title" style="border-color: #ff6666" placeholder="This field is required" />

                    <button type="submit">Add</button>

                <?php } else { ?>

                    <input type="text" name="title" placeholder="Enter your task here" />

                    <button type="submit">Add a Task</button>

                <?php } ?>
            </form>
        </div>

        <?php $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC"); ?>

        <div class="show-todo-section">

            <?php if ($todos->rowCount() <= 0) { ?>

                <div class="todo-item">

                    <div class="empty">

                        <img src="img/f.png" width="100%" />
                        <img src="img/Ellipsis.gif" width="80px">

                    </div>

                </div>

            <?php } ?>

            <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>

                <div class="todo-item">

                    <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>

                    <?php if ($todo['checked']) { ?>

                        <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />

                        <h2 class="checked"><?php echo $todo['title'] ?></h2>

                    <?php } else { ?>

                        <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />

                        <h2><?php echo $todo['title'] ?></h2>

                    <?php } ?>

                    <br>

                    <small>created: <?php echo $todo['date_time'] ?></small>

                </div>
            <?php } ?>

        </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.remove-to-do').click(function() {
                const id = $(this).attr('id');

                $.post("app/remove.php", {
                        id: id
                    },
                    (data) => {
                        if (data) {
                            $(this).parent().hide(600);
                        }
                    }
                );
            });

            $(".check-box").click(function(e) {
                const id = $(this).attr('data-todo-id');

                $.post('app/check.php', {
                        id: id
                    },
                    (data) => {
                        if (data != 'error') {
                            const h2 = $(this).next();
                            if (data === '1') {
                                h2.removeClass('checked');
                            } else {
                                h2.addClass('checked');
                            }
                        }
                    }
                );
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>