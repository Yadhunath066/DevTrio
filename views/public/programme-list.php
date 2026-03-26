<?php
// This file displays the list of programmes
// Variables available: $undergraduate, $postgraduate
?>

<div class="programme-section">
    <h2>Undergraduate Programmes</h2>
    <div class="programme-grid">
        <?php if(empty($undergraduate)): ?>
            <p>No undergraduate programmes available.</p>
        <?php else: ?>
            <?php foreach($undergraduate as $prog): ?>
            <div class="programme-card">
                <?php if(!empty($prog['Image'])): ?>
                    <img src="/DevTrio/<?php echo $prog['Image']; ?>" 
                         alt="<?php echo htmlspecialchars($prog['ProgrammeName']); ?>"
                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px 8px 0 0;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 180px; display: flex; align-items: center; justify-content: center; color: white; border-radius: 8px 8px 0 0;">
                        📚 <?php echo htmlspecialchars($prog['ProgrammeName']); ?>
                    </div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 100)) . '...'; ?></p>
                <p><strong>Level:</strong> <?php echo $prog['LevelName']; ?></p>
                <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details →</a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <h2>Postgraduate Programmes</h2>
    <div class="programme-grid">
        <?php if(empty($postgraduate)): ?>
            <p>No postgraduate programmes available.</p>
        <?php else: ?>
            <?php foreach($postgraduate as $prog): ?>
            <div class="programme-card">
                <?php if(!empty($prog['Image'])): ?>
                    <img src="/DevTrio/<?php echo $prog['Image']; ?>" 
                         alt="<?php echo htmlspecialchars($prog['ProgrammeName']); ?>"
                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px 8px 0 0;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 180px; display: flex; align-items: center; justify-content: center; color: white; border-radius: 8px 8px 0 0;">
                        📚 <?php echo htmlspecialchars($prog['ProgrammeName']); ?>
                    </div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 100)) . '...'; ?></p>
                <p><strong>Level:</strong> <?php echo $prog['LevelName']; ?></p>
                <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details →</a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



