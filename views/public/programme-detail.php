<?php
// This file displays programme details
// Variables available: $programme, $modules
?>

<div class="programme-detail">
    <?php if(!empty($programme['Image'])): ?>
        <img src="/DevTrio/<?php echo $programme['Image']; ?>" 
             alt="<?php echo htmlspecialchars($programme['ProgrammeName']); ?>"
             style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px; margin-bottom: 20px;">
    <?php endif; ?>
    
    <h1><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h1>
    <p class="level"><?php echo htmlspecialchars($programme['LevelName']); ?></p>
    
    <div class="description">
        <h2>Programme Description</h2>
        <p><?php echo htmlspecialchars($programme['Description']); ?></p>
    </div>
    
    <div class="modules">
        <h2>Modules by Year</h2>
        
        <?php if(empty($modules)): ?>
            <p>No modules available for this programme.</p>
        <?php else: ?>
            <?php 
            $currentYear = 0;
            foreach($modules as $module): 
                if($module['year'] != $currentYear):
                    if($currentYear != 0) echo '</div>';
                    $currentYear = $module['year'];
                    echo '<div class="year-group">';
                    echo '<h3>Year ' . $currentYear . '</h3>';
                endif;
            ?>
            
            <div class="module-card">
                <h4><?php echo htmlspecialchars($module['name']); ?></h4>
                <p><?php echo htmlspecialchars($module['description']); ?></p>
                <p class="module-leader">Module Leader: <?php echo htmlspecialchars($module['leader']); ?></p>
            </div>
            
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="interest-form">
        <h2>Register Your Interest</h2>
        <form action="index.php?url=interest" method="POST">
            <input type="hidden" name="programme_id" value="<?php echo $programme['ProgrammeID']; ?>">
            
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit" class="btn">Register Interest</button>
        </form>
        <p style="margin-top: 15px; font-style: italic; color: #666;">
            Register your interest and we'll send you updates about this programme.
        </p>
    </div>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="index.php?url=programmes" class="btn">← Back to All Programmes</a>
    </div>
</div>