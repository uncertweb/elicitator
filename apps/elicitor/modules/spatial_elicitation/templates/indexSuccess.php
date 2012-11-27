<h1>Spatial elicitations List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Read briefing document</th>
      <th>Minimum</th>
      <th>Maximum</th>
      <th>Lower</th>
      <th>Median</th>
      <th>Upper</th>
      <th>Enabled</th>
      <th>Expert</th>
      <th>Variable</th>
      <th>Opt out</th>
      <th>Reason</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($spatial_elicitations as $spatial_elicitation): ?>
    <tr>
      <td><a href="<?php echo url_for('spatial_elicitation/edit?id='.$spatial_elicitation->getId()) ?>"><?php echo $spatial_elicitation->getId() ?></a></td>
      <td><?php echo $spatial_elicitation->getReadBriefingDocument() ?></td>
      <td><?php echo $spatial_elicitation->getMinimum() ?></td>
      <td><?php echo $spatial_elicitation->getMaximum() ?></td>
      <td><?php echo $spatial_elicitation->getLower() ?></td>
      <td><?php echo $spatial_elicitation->getMedian() ?></td>
      <td><?php echo $spatial_elicitation->getUpper() ?></td>
      <td><?php echo $spatial_elicitation->getEnabled() ?></td>
      <td><?php echo $spatial_elicitation->getExpertId() ?></td>
      <td><?php echo $spatial_elicitation->getVariableId() ?></td>
      <td><?php echo $spatial_elicitation->getOptOut() ?></td>
      <td><?php echo $spatial_elicitation->getReason() ?></td>
      <td><?php echo $spatial_elicitation->getCreatedAt() ?></td>
      <td><?php echo $spatial_elicitation->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('spatial_elicitation/new') ?>">New</a>
