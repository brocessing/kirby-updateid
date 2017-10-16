<?php

kirby()->hook('panel.page.move', function($page, $oldPage) {
  $config = c::get('plugin.updateid');
  if (!is_array($config)) return;

  $oldId = $oldPage->id();
  $regex = '/([^a-z0-9-]|^)' . preg_quote($oldId, '/') . '([^a-z0-9-]|$)/m';
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
          if (!preg_match_all($regex, $fieldValue)) continue;
          $data[$fieldName] = preg_replace($regex, "$1".$newId."$2", $fieldValue);
          // Used for debug
          // $message = "\n" . 'Update:';
          // $message .= "\n" . '    Page: ' . $target->id();
          // $message .= "\n" . '    Field: ' . $fieldName;
          // $message .= "\n" . '    Lang: ' . $lang;
          // $message .= "\n" . '    From: ' . $fieldValue;
          // $message .= "\n" . '    To: ' . $data[$fieldName];
          // error_log(var_export($message, true));
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
