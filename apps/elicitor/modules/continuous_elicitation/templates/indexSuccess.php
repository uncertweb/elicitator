<h1>Continuous elicitations List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Minimum</th>
      <th>Maximum</th>
      <th>Tenth</th>
      <th>Median</th>
      <th>Ninetieth</th>
      <th>Expert</th>
      <th>Variable</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($continuous_elicitations as $continuous_elicitation): ?>
    <tr>
      <td><a href="<?php echo url_for('continuous_elicitation/edit?id='.$continuous_elicitation->getId()) ?>"><?php echo $continuous_elicitation->getId() ?></a></td>
      <td><?php echo $continuous_elicitation->getMinimum() ?></td>
      <td><?php echo $continuous_elicitation->getMaximum() ?></td>
      <td><?php echo $continuous_elicitation->getTenth() ?></td>
      <td><?php echo $continuous_elicitation->getMedian() ?></td>
      <td><?php echo $continuous_elicitation->getNinetieth() ?></td>
      <td><?php echo $continuous_elicitation->getExpertId() ?></td>
      <td><?php echo $continuous_elicitation->getVariableId() ?></td>
      <td><?php echo $continuous_elicitation->getCreatedAt() ?></td>
      <td><?php echo $continuous_elicitation->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('continuous_elicitation/new') ?>">New</a>
