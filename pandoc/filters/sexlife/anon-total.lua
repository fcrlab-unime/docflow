function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('sexlife') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('sexlife')
  span.classes:remove(position)
end
return span
end