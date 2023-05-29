function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('date') and span.classes:includes('month') then
  span.content = pandoc.Str("xx")
  local value, position = span.classes:find('month')
  span.classes:remove(position)
  local value, position = span.classes:find('date')
  span.classes:remove(position)
end
return span
end