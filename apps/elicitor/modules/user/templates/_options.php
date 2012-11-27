<ul id="signin">
    <li><a id="signin-button"><?php echo $sf_user->getGuardUser()->getFullName(); ?></a></li>
</ul>
<div id="signin-container">
    <div id="signin-box">
        <ul id="profile-options">
            <li>
                <a id="option-home" href="<?php echo url_for('control_panel'); ?>">control panel</a>
            </li>
            <li>
                <a id="option-my-experts" href="<?php echo url_for('@expert_list?id='. $sf_user->getGuardUser()->getId()); ?>">experts</a>
            </li>
            <li>
                <a id="option-problems" href="<?php echo url_for('problems'); ?>">elicitation problems</a>
            </li>
            <li>
                <a id="option-elicitations" href="<?php echo url_for('tasks'); ?>">elicitation tasks</a>
            </li>
            <li>
                <a id="option-edit-profile" href="<?php echo url_for('user_edit', $sf_user->getGuardUser());?>">edit account details</a>
            </li>
            <li>
                <a id="option-logout" href="<?php echo url_for('@sf_guard_signout'); ?>">log out</a>
            </li>
        </ul>
    </div>
</div>