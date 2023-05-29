function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('otherdata') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('otherdata')
  span.classes:remove(position)
end
return span
end