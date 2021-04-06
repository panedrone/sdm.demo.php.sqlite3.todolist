<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TODO-list</title>
        <style>
            body {
                font-family: 'trebuchet MS', 'Lucida sans', Arial;
                /*font-family: Arial, Helvetica, sans-serif;*/
                /*font-family: Verdana, Geneva, sans-serif;*/
            }

            .bg {
                background-color: #f0f0f0;
                font-size: 12px;
            }

            .bg td {
                vertical-align: top;
                /*padding: 4pt;*/
            }

            .form-label {
                padding: 3pt;
            }

            .section {
                font-family: arial,sans-serif;
                font-size:12px;
                color:#333333;
                border: solid 1px #666;
                border-collapse: collapse;
            }
            .section th {
                border: solid 1px #025B85;
                padding: 8px;
                background-color: #028Bc5;
                color:#fff;
            }
            .section td {
                padding: 8px;
                border: solid 1px #666;
                background-color: #ffffff;
            }

        </style>
    </head>
    <body>
        <table cellpadding=10 class="bg">
            <tr>
                <?php
                define("ABS_PATH", dirname(__FILE__));

                include_once ABS_PATH . '/dal/DataStore.php';
                include_once ABS_PATH . '/dal/GroupsDao.php';
                include_once ABS_PATH . '/dal/TasksDao.php';

                $ds = new DataStore();
                $ds->open();
                $group_dao = new dao\GroupsDao($ds);
                $task_dao = new dao\TasksDao($ds);

                $g_id = filter_input(INPUT_GET, "g_id", FILTER_SANITIZE_NUMBER_INT);
                $groups = $group_dao->getGroups();
                ?>
                <td>
                    <table border cellspacing=1 cellpadding=5 class="section">
                        <tr>
                            <th>Group</th>
                            <th>Tasks</th>
                            <th></th>
                        </tr>
                        <?php
                        foreach ($groups as $g): // $o->... are visible for code assistance
                            ?>
                            <tr>
                                <td>
                                    <a href="index.php?g_id=<?php echo $g->getGId(); ?>">
                                        <?php echo $g->getGName(); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $g->getTasksCount() ?>
                                </td>
                                <td>
                                    <a href="javascript:group_update(<?php echo $g->getGId(); ?>);">u</a>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </table>
                    <form action="group_create_action.php" method="POST">
                        <input type="text" name="g_name" id="g_name" />
                        <input type="submit" value="+"/>
                    </form>
                </td>
                <?php
                $task_create_url = "task_create.php?g_id=";
                if ($g_id != NULL):
                    $task_create_url = $task_create_url . $g_id;
                    $tasks = $task_dao->getGroupTasks($g_id);
                    $group = $group_dao->readGroup($g_id);
                    if ($group != false): // it may be false after removal
                        ?>
                        <td>
                            <form action="group_update_action.php?g_id=<?php echo $g->getGId(); ?>" method="POST">
                                <input type="text" name="g_name" id="g_name" value="<?php echo $group->getGName(); ?>"/>
                                <input type="submit" value="^"/>
                                <a href="group_delete_action.php?g_id=<?php echo $g->getGId(); ?>"><input type="button" value="x"/></a>
                            </form> 
                            <table border cellspacing=1 cellpadding=5 class="section">
                                <tr>
                                    <th>Date</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                </tr>
                                <?php
                                foreach ($tasks as $t): // $o->... are visible for code assistance
                                    ?>
                                    <tr>
                                        <td><?php echo $t->getTDate(); ?></td>
                                        <td>
                                            <a href="index.php?g_id=<?php echo $t->getGId() . '&t_id=' . $t->getTId(); ?>">
                                                <?php echo $t->getTSubject(); ?>
                                            </a>
                                        </td>
                                        <td><?php echo $t->getTPriority(); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                            </table>
                            <form action="task_create_action.php?g_id=<?php echo $g_id; ?>" method="POST">
                                <input type="text" name="t_subject_1" id="t_subject_1" />
                                <input type="submit" value="+" />
                            </form>
                        </td>
                        <?php
                        $t_id = filter_input(INPUT_GET, "t_id", FILTER_SANITIZE_NUMBER_INT);
                        if ($t_id):
                            $task = $task_dao->readTask($t_id);
                            if ($task != false): // it may be false after removal
                                ?>
                                <td>
                                    <?php
                                    $dt = $task->getTDate();
                                    $sbj = $task->getTSubject();
                                    $priority = $task->getTPriority();
                                    $comments = $task->getTComments();
                                    ?>
                                    <span style="font-size: 16pt;"><?php echo $dt . " " . $sbj; ?></span>
                                    <form action="task_update_action.php?g_id=<?php echo $t->getGId(); ?>&t_id=<?php echo $t->getTId(); ?>" method="POST">
                                        <table>
                                            <tr>
                                                <td class="form-label">Date</td>
                                                <td>
                                                    <input type="text" name="t_date" id="t_date" value="<?php echo $dt; ?>" />
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="form-label">Subject</td>
                                                <td>
                                                    <input type="text" name="t_subject" id="t_subject" value="<?php echo $sbj; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form-label">Priority</td>
                                                <td>
                                                    <input type="text" name="t_priority" id="t_priority" value="<?php echo $priority; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form-label">Comments</td>
                                                <td>
                                                    <textarea name="t_comments" id="t_comments" cols="40" rows="5"><?php echo $comments; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                        <div align="right">
                                            <p style="text-align: center;">
                                                <input type="submit" value="^"/>
                                                <a href="task_delete_action.php?t_id=<?php echo $t->getTId(); ?>">
                                                    <input type="button" value="x"/>
                                                </a>
                                                <a href="index.php?g_id=<?php echo $g_id; ?>">
                                                    <input type="button" value="&lt;"/>
                                                </a>
                                            </p>
                                        </div>
                                    </form>                                   
                                </td>
                                <?php
                            endif;
                        endif;
                    endif;
                endif;
                $ds->close();
                ?>
            </tr>
        </table>
    </body>
</html>