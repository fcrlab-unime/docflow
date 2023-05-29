function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('name') then
  span.content = pandoc.Str('xxxxx')
  local value, position = span.classes:find('name')
  span.classes:remove(position)
end
return span
end