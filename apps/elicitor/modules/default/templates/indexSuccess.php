<?php use_javascript('jquery.featureList.js'); ?>
<?php use_javascript('main_page.js'); ?>

<div class="grid_11">
    <div id="feature_list" class="shadow">
        <ul id="tabs">
            <li>
                <a href="javascript:;">
                    <h3>Domain experts</h3>
                    <span>Manage your network of domain experts.</span>
                </a>
            </li>
            <li>
                <a href="javascript:;">
                    <h3>Elicit judgements</h3>
                    <span>Handle discrete, continuous and spatial variables.</span>
                </a>
            </li>
            <li>
                <a href="javascript:;">
                    <h3>Monitor progress</h3>
                    <span>View the current status of all your variables</span>
                </a>
            </li>

        </ul>
        <ul id="output">
            <li>
                <img src="images/books.jpg" />

            </li>
            <li>
                <img src="images/clipboard.jpg" />
            </li>
            <li>
                <img src="images/progress.jpg" />
            </li>
        </ul>

    </div>
    <div class=" grid_3 alpha">
        <h1 class="blue">What is it?</h1>
        <p>
            <strong>Expert elicitation</strong> is the process of synthesising expert opinions on a subject where there is uncertainty due to insufficient data, when such data is unattainable because of physical constraints or lack of resources.
        </p>
    </div>
    <div class="prefix_1 grid_3">
        <h1 class="green">How it works</h1>
        <p>
            The Elicitator allows you to create 'elicitation problems' with many uncertain variables. You can invite a number of domain experts to make judgements about the uncertainty within these variables, culminating in a probability distribution.
        </p>
    </div>
    <div class="prefix_1 grid_3 omega">
        <h1 class="red">Register today</h1>
        <p>
            Registration is <strong>free</strong> and should only take <strong>60 seconds</strong>. Once you are registered, create an elicitation problem and begin understanding the uncertainties about the data you work with.
        </p>

        <a href="<?php echo url_for('@register'); ?>" class="signup">
            <span>create an account</span>
        </a>
    </div>
    <p>
        &nbsp;
    </p>
</div>
