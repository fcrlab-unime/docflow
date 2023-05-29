function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('politicalopinion') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('politicalopinion')
  span.classes:remove(position)
end
return span
end