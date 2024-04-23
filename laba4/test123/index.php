<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "forum_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['create_channel'])) {
    $channel_name = $_POST['channel_name'];
    $sql = "INSERT INTO channels (channel_name) VALUES ('$channel_name')";
    if ($conn->query($sql) === TRUE) {
        echo "Канал успешно создан";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}


if (isset($_POST['create_topic'])) {
    $topic_name = $_POST['topic_name'];
    $channel_id = $_POST['channel_id'];
    $sql = "INSERT INTO topics (topic_name, channel_id) VALUES ('$topic_name', '$channel_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Тема успешно создана";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}


if (isset($_POST['delete_topic'])) {
    $topic_id = $_POST['topic_id'];
    
    $sql_delete_messages = "DELETE FROM messages WHERE topic_id='$topic_id'";
    if ($conn->query($sql_delete_messages) === TRUE) {

        $sql_delete_topic = "DELETE FROM topics WHERE id='$topic_id'";
        if ($conn->query($sql_delete_topic) === TRUE) {
            echo "Тема успешно удалена";
        } else {
            echo "Ошибка при удалении темы: " . $conn->error;
        }
    } else {
        echo "Ошибка при удалении сообщений: " . $conn->error;
    }
}

if (isset($_POST['submit_message'])) {
    $topic_id = $_POST['topic_id'];
    $message_text = $_POST['message_text'];

    preg_match('/#(\w+)/u', $message_text, $matches);
    if(isset($matches[1])) {
        $topic_name = $matches[1];

        $default_channel_id = 1;

        $check_topic_sql = "SELECT * FROM topics WHERE topic_name = '$topic_name'";
        $check_topic_result = $conn->query($check_topic_sql);

        if ($check_topic_result->num_rows == 0) {

            $sql = "INSERT INTO topics (topic_name, channel_id) VALUES ('$topic_name', '$default_channel_id')";
            if ($conn->query($sql) === TRUE) {
                echo "Тема успешно создана";

                $topic_id = $conn->insert_id;
            } else {
                echo "Ошибка: " . $sql . "<br>" . $conn->error;
            }
        } else {

            $topic_row = $check_topic_result->fetch_assoc();
            $topic_id = $topic_row['id'];
        }
    }


    $message_text .= "\n----------------------------------\n";
    $sql = "INSERT INTO messages (topic_id, message_text) VALUES ('$topic_id', '$message_text')";
    if ($conn->query($sql) === TRUE) {
        echo "Сообщение успешно отправлено";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

$sql_channels = "SELECT * FROM channels";
$result_channels = $conn->query($sql_channels);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Хештег сортер</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <h1>Хештег сортер</h1>
    <div class=wrapper>

    <h2>Создать канал</h2>
    <form method="post" action="">
        <input type="text" name="channel_name" placeholder="Название канала" required>
        <button type="submit" name="create_channel">Создать</button>
    </form>

    <h2>Создать тему</h2>
    <form method="post" action="">
        <input type="text" name="topic_name" placeholder="Название темы" required>
        <select name="channel_id">
            <?php while ($row_channel = $result_channels->fetch_assoc()): ?>
                <option value="<?php echo $row_channel['id']; ?>"><?php echo $row_channel['channel_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="create_topic">Создать</button>
    </form>

    <h2>Отправить сообщение</h2>
    <form method="post" action="">
        <select name="topic_id">
            <?php
            if (isset($_POST['selected_channel'])) {
                $selected_channel = $_POST['selected_channel'];
                $sql_topics = "SELECT * FROM topics WHERE channel_id = '$selected_channel'";
                $result_topics = $conn->query($sql_topics);
                while ($row_topic = $result_topics->fetch_assoc()): ?>
                    <option value="<?php echo $row_topic['id']; ?>"><?php echo $row_topic['topic_name']; ?></option>
                <?php endwhile;
            }
            ?>
        </select><br>
        <textarea name="message_text" placeholder="Текст сообщения" required></textarea><br>
        <button type="submit" name="submit_message">Отправить</button>
    </form>

    <h2>Каналы и темы</h2>
    <form method="post" action="">
        <select name="selected_channel">
            <?php
            $result_channels->data_seek(0);
            while ($row_channel = $result_channels->fetch_assoc()): ?>
                <option value="<?php echo $row_channel['id']; ?>"><?php echo $row_channel['channel_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="view_topics">Показать темы</button>
    </form>
    </div>

    <?php
    if (isset($_POST['view_topics'])) {
        $selected_channel = $_POST['selected_channel'];
        $sql_topics = "SELECT * FROM topics WHERE channel_id = '$selected_channel'";
        $result_topics = $conn->query($sql_topics);

        if ($result_topics->num_rows > 0) {
            while ($row_topic = $result_topics->fetch_assoc()) {
                echo "<p><strong>" . $row_topic['topic_name'] . "</strong>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='topic_id' value='" . $row_topic['id'] . "'>";
                echo "<button type='submit' name='delete_topic'>Удалить тему</button>";
                echo "</form></p>";
                echo "<ul>";
                $topic_id = $row_topic['id'];
                $sql_messages = "SELECT * FROM messages WHERE topic_id = '$topic_id'";
                $result_messages = $conn->query($sql_messages);
                if ($result_messages->num_rows > 0) {
                    while ($row_message = $result_messages->fetch_assoc()) {
                        echo "<li>" . nl2br($row_message['message_text']) . "</li>";
                    }
                } else {
                    echo "<li>Нет сообщений для этой темы</li>";
                }
                echo "</ul>";
            }
        } else {
            echo "Нет тем для этого канала";
        }
    }
    ?>

    <?php $conn->close(); ?>
</body>
</html>