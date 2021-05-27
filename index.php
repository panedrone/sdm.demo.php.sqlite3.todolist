<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles.css" type="text/css"/>
        <title>TODO</title>
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
                                <td align="center">
                                    <?php echo $g->getTasksCount() ?>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </table>
                    <form action="group_create_action.php" method="POST">
                        <table class="controls">
                            <tr>
                                <td>
                                    <input type="text" name="g_name" id="g_name" />
                                </td>
                                <td width="1">
                                    <input type="submit" value="+"/>
                                </td>
                            </tr>
                        </table>
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
                            <form action="group_update_action.php?g_id=<?php echo $g_id; ?>" method="POST">
                                <table class="controls">
                                    <tr>
                                        <td>
                                            <input  style="font-size: 14pt;" type="text" name="g_name" id="g_name" value="<?php echo $group->getGName(); ?>"/>
                                        </td>
                                        <td width="1">
                                            <input type="submit" value="!"/>
                                        </td>
                                        <td width="1">
                                            <a href="group_delete_action.php?g_id=<?php echo $g->getGId(); ?>"><input type="button" value="x"/></a>
                                        </td>
                                        <td width="1">
                                            <a href="index.php">
                                                <input type="button" value="&lt;"/>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
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
                                        <td align="center"><?php echo $t->getTPriority(); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                            </table>
                            <form action="task_create_action.php?g_id=<?php echo $g_id; ?>" method="POST">
                                <table class="controls">
                                    <tr>
                                        <td>
                                            <input type="text" name="t_subject_1" id="t_subject_1" />
                                        </td>
                                        <td width="1">
                                            <input type="submit" value="+" />
                                        </td>
                                    </tr>
                                </table>
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
                                    <form action="task_update_action.php?g_id=<?php echo $t->getGId(); ?>&t_id=<?php echo $t->getTId(); ?>" method="POST">
                                        <table class="controls">
                                            <tr>
                                                <td>
                                                    <div class="title"><?php echo $dt . " " . $sbj; ?></div>
                                                </td>
                                                <td width="1">
                                                    <input type="submit" value="!"/>
                                                </td>
                                                <td width="1">
                                                    <a href="task_delete_action.php?t_id=<?php echo $t->getTId(); ?>">
                                                        <input type="button" value="x"/>
                                                    </a>
                                                </td>
                                                <td width="1">
                                                    <a href="index.php?g_id=<?php echo $g_id; ?>">
                                                        <input type="button" value="&lt;"/>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
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
                                                    <textarea name="t_comments" id="t_comments" cols="40" rows="8"><?php echo $comments; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>                                   
                                </td>
                                <?php
                            endif;
                            ?>
                            <?php
                        endif;
                        ?>
                        <?php
                    endif;
                    ?>
                    <?php
                endif;
                $ds->close();
                ?>
            </tr>
        </table>
    </body>
</html>