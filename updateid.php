<?php

kirby()->hook('panel.page.move', function($page, $oldPage) {
  $config = c::get('plugin.updateid');
  if (!is_array($config)) return;

  $oldId = $oldPage->id();
  $newId = $page->id();
  if ($oldId === $newId) return;

  foreach($config as $e) {

    if (
      !is_array($e) ||
      !isset($e['fields']) ||
      !isset($e['pages']) ||
      (!is_string($e['fields']) && !is_array($e['fields']))
    ) continue;

    $fields = is_array($e['fields']) ? $e['fields'] : array($e['fields']);

    $targetpages = (is_callable($e['pages']) && !is_string($e['pages']))
      ? $e['pages']()
      : $e['pages'];

    $targets = is_array($targetpages) ? pages($targetpages) : pages(array($targetpages));
    $targets->first();
    // Iterate on each page target by the updateid configuration
    while ($targets->current()) {
      $target = $targets->current();

      // If the site is not multilang we still initialize an array for convenience
      $isMultilang = site()->multilang();
      $languages = $isMultilang
        ? array_values(array_map(function($lang) { return $lang->code(); }, site()->languages()->toArray()))
        : array(NULL);

      // Iterate on each language
      foreach($languages as $lang) {
        $data = array();
        foreach($fields as $fieldName) {
          if (!$target->{$fieldName}()->exists() || $target->{$fieldName}()->empty()) continue;
          $fieldValue = $isMultilang
            ? $target->content($lang)->{$fieldName}()->value()
            : $target->{$fieldName}()->value();
          if (strpos($fieldValue, $oldId) === false) continue;
          $data[$fieldName] = str_replace($oldId, $newId, $fieldValue);
        }
        if (count($data) > 0) {
          if ($isMultilang) $target->update($data, $lang);
          else $target->update($data);
        }
      }

      $targets->next();
    }
  }
});
